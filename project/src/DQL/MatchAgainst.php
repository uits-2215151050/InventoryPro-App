<?php

namespace App\DQL;

use Doctrine\ORM\Query\AST\Functions\FunctionNode;
use Doctrine\ORM\Query\Parser;
use Doctrine\ORM\Query\SqlWalker;
use Doctrine\ORM\Query\TokenType;

class MatchAgainst extends FunctionNode
{
    /** @var array */
    public $pathExp = [];

    /** @var mixed */
    public $against = null;

    /** @var bool */
    public $booleanMode = false;

    /** @var bool */
    public $queryExpansion = false;

    public function parse(Parser $parser): void
    {
        // MATCH
        $parser->match(TokenType::T_IDENTIFIER);
        $parser->match(TokenType::T_OPEN_PARENTHESIS);

        // first field
        $this->pathExp[] = $parser->StateFieldPathExpression();

        // optional other fields
        $lexer = $parser->getLexer();
        while ($lexer->isNextToken(TokenType::T_COMMA)) {
            $parser->match(TokenType::T_COMMA);
            $this->pathExp[] = $parser->StateFieldPathExpression();
        }

        $parser->match(TokenType::T_CLOSE_PARENTHESIS);

        // AGAINST
        if (strcasecmp($lexer->lookahead->value, 'against') !== 0) {
            $parser->syntaxError('AGAINST');
        }
        $parser->match(TokenType::T_IDENTIFIER);

        $parser->match(TokenType::T_OPEN_PARENTHESIS);
        $this->against = $parser->StringPrimary();

        if ($lexer->isNextToken(TokenType::T_IN) || ($lexer->isNextToken(TokenType::T_IDENTIFIER) && strcasecmp($lexer->lookahead->value, 'IN') === 0)) {
            // Handle IN
            if ($lexer->isNextToken(TokenType::T_IN)) {
                $parser->match(TokenType::T_IN);
            } else {
                $parser->match(TokenType::T_IDENTIFIER);
            }

            $parser->match(TokenType::T_IDENTIFIER); // BOOLEAN

            if (strcasecmp($lexer->lookahead->value, 'MODE') === 0) {
                $parser->match(TokenType::T_IDENTIFIER); // MODE
            }
            $this->booleanMode = true;
        } elseif ($lexer->isNextToken(TokenType::T_WITH) || ($lexer->isNextToken(TokenType::T_IDENTIFIER) && strcasecmp($lexer->lookahead->value, 'WITH') === 0)) {
            // Handle WITH
            if ($lexer->isNextToken(TokenType::T_WITH)) {
                $parser->match(TokenType::T_WITH);
            } else {
                $parser->match(TokenType::T_IDENTIFIER);
            }

            $parser->match(TokenType::T_IDENTIFIER); // QUERY
            $parser->match(TokenType::T_IDENTIFIER); // EXPANSION
            $this->queryExpansion = true;
        }

        $parser->match(TokenType::T_CLOSE_PARENTHESIS);
    }

    public function getSql(SqlWalker $sqlWalker): string
    {
        $fields = [];
        foreach ($this->pathExp as $pathExp) {
            $fields[] = $pathExp->dispatch($sqlWalker);
        }

        $against = $sqlWalker->walkStringPrimary($this->against)
            . ($this->booleanMode ? ' IN BOOLEAN MODE' : '')
            . ($this->queryExpansion ? ' WITH QUERY EXPANSION' : '');

        return sprintf('MATCH (%s) AGAINST (%s)', implode(', ', $fields), $against);
    }
}
