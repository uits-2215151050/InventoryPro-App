<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260106091457 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE comment (id INT AUTO_INCREMENT NOT NULL, inventory_id INT NOT NULL, author_id INT NOT NULL, content LONGTEXT NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_9474526C9EEA759 (inventory_id), INDEX IDX_9474526CF675F31B (author_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE inventory (id INT AUTO_INCREMENT NOT NULL, creator_id INT NOT NULL, category_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, image_url VARCHAR(500) DEFAULT NULL, is_public TINYINT(1) NOT NULL, version INT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, custom_id_format JSON DEFAULT NULL, sequence_counter INT NOT NULL, custom_string1_state TINYINT(1) NOT NULL, custom_string1_name VARCHAR(255) DEFAULT NULL, custom_string1_description VARCHAR(500) DEFAULT NULL, custom_string1_show_in_table TINYINT(1) NOT NULL, custom_string1_order SMALLINT NOT NULL, custom_string2_state TINYINT(1) NOT NULL, custom_string2_name VARCHAR(255) DEFAULT NULL, custom_string2_description VARCHAR(500) DEFAULT NULL, custom_string2_show_in_table TINYINT(1) NOT NULL, custom_string2_order SMALLINT NOT NULL, custom_string3_state TINYINT(1) NOT NULL, custom_string3_name VARCHAR(255) DEFAULT NULL, custom_string3_description VARCHAR(500) DEFAULT NULL, custom_string3_show_in_table TINYINT(1) NOT NULL, custom_string3_order SMALLINT NOT NULL, custom_text1_state TINYINT(1) NOT NULL, custom_text1_name VARCHAR(255) DEFAULT NULL, custom_text1_description VARCHAR(500) DEFAULT NULL, custom_text1_show_in_table TINYINT(1) NOT NULL, custom_text1_order SMALLINT NOT NULL, custom_text2_state TINYINT(1) NOT NULL, custom_text2_name VARCHAR(255) DEFAULT NULL, custom_text2_description VARCHAR(500) DEFAULT NULL, custom_text2_show_in_table TINYINT(1) NOT NULL, custom_text2_order SMALLINT NOT NULL, custom_text3_state TINYINT(1) NOT NULL, custom_text3_name VARCHAR(255) DEFAULT NULL, custom_text3_description VARCHAR(500) DEFAULT NULL, custom_text3_show_in_table TINYINT(1) NOT NULL, custom_text3_order SMALLINT NOT NULL, custom_number1_state TINYINT(1) NOT NULL, custom_number1_name VARCHAR(255) DEFAULT NULL, custom_number1_description VARCHAR(500) DEFAULT NULL, custom_number1_show_in_table TINYINT(1) NOT NULL, custom_number1_order SMALLINT NOT NULL, custom_number2_state TINYINT(1) NOT NULL, custom_number2_name VARCHAR(255) DEFAULT NULL, custom_number2_description VARCHAR(500) DEFAULT NULL, custom_number2_show_in_table TINYINT(1) NOT NULL, custom_number2_order SMALLINT NOT NULL, custom_number3_state TINYINT(1) NOT NULL, custom_number3_name VARCHAR(255) DEFAULT NULL, custom_number3_description VARCHAR(500) DEFAULT NULL, custom_number3_show_in_table TINYINT(1) NOT NULL, custom_number3_order SMALLINT NOT NULL, custom_link1_state TINYINT(1) NOT NULL, custom_link1_name VARCHAR(255) DEFAULT NULL, custom_link1_description VARCHAR(500) DEFAULT NULL, custom_link1_show_in_table TINYINT(1) NOT NULL, custom_link1_order SMALLINT NOT NULL, custom_link2_state TINYINT(1) NOT NULL, custom_link2_name VARCHAR(255) DEFAULT NULL, custom_link2_description VARCHAR(500) DEFAULT NULL, custom_link2_show_in_table TINYINT(1) NOT NULL, custom_link2_order SMALLINT NOT NULL, custom_link3_state TINYINT(1) NOT NULL, custom_link3_name VARCHAR(255) DEFAULT NULL, custom_link3_description VARCHAR(500) DEFAULT NULL, custom_link3_show_in_table TINYINT(1) NOT NULL, custom_link3_order SMALLINT NOT NULL, custom_bool1_state TINYINT(1) NOT NULL, custom_bool1_name VARCHAR(255) DEFAULT NULL, custom_bool1_description VARCHAR(500) DEFAULT NULL, custom_bool1_show_in_table TINYINT(1) NOT NULL, custom_bool1_order SMALLINT NOT NULL, custom_bool2_state TINYINT(1) NOT NULL, custom_bool2_name VARCHAR(255) DEFAULT NULL, custom_bool2_description VARCHAR(500) DEFAULT NULL, custom_bool2_show_in_table TINYINT(1) NOT NULL, custom_bool2_order SMALLINT NOT NULL, custom_bool3_state TINYINT(1) NOT NULL, custom_bool3_name VARCHAR(255) DEFAULT NULL, custom_bool3_description VARCHAR(500) DEFAULT NULL, custom_bool3_show_in_table TINYINT(1) NOT NULL, custom_bool3_order SMALLINT NOT NULL, INDEX IDX_B12D4A3661220EA6 (creator_id), INDEX IDX_B12D4A3612469DE2 (category_id), FULLTEXT INDEX idx_inventory_fulltext (title, description), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE inventory_tag (inventory_id INT NOT NULL, tag_id INT NOT NULL, INDEX IDX_7AE3D8E79EEA759 (inventory_id), INDEX IDX_7AE3D8E7BAD26311 (tag_id), PRIMARY KEY(inventory_id, tag_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE inventory_writers (inventory_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_592B2B239EEA759 (inventory_id), INDEX IDX_592B2B23A76ED395 (user_id), PRIMARY KEY(inventory_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE item (id INT AUTO_INCREMENT NOT NULL, inventory_id INT NOT NULL, created_by_id INT NOT NULL, custom_id VARCHAR(255) NOT NULL, version INT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, custom_string1 VARCHAR(255) DEFAULT NULL, custom_string2 VARCHAR(255) DEFAULT NULL, custom_string3 VARCHAR(255) DEFAULT NULL, custom_text1 LONGTEXT DEFAULT NULL, custom_text2 LONGTEXT DEFAULT NULL, custom_text3 LONGTEXT DEFAULT NULL, custom_number1 DOUBLE PRECISION DEFAULT NULL, custom_number2 DOUBLE PRECISION DEFAULT NULL, custom_number3 DOUBLE PRECISION DEFAULT NULL, custom_link1 VARCHAR(1000) DEFAULT NULL, custom_link2 VARCHAR(1000) DEFAULT NULL, custom_link3 VARCHAR(1000) DEFAULT NULL, custom_bool1 TINYINT(1) DEFAULT NULL, custom_bool2 TINYINT(1) DEFAULT NULL, custom_bool3 TINYINT(1) DEFAULT NULL, INDEX IDX_1F1B251E9EEA759 (inventory_id), INDEX IDX_1F1B251EB03A8386 (created_by_id), FULLTEXT INDEX idx_item_fulltext (custom_id, custom_string1, custom_string2, custom_string3), UNIQUE INDEX unique_custom_id_per_inventory (inventory_id, custom_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `like` (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, item_id INT NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_AC6340B3A76ED395 (user_id), INDEX IDX_AC6340B3126F525E (item_id), UNIQUE INDEX unique_like_per_user_item (user_id, item_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tag (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) NOT NULL, UNIQUE INDEX UNIQ_389B7835E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `user` (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, name VARCHAR(255) DEFAULT NULL, roles JSON NOT NULL, password VARCHAR(255) DEFAULT NULL, google_id VARCHAR(255) DEFAULT NULL, facebook_id VARCHAR(255) DEFAULT NULL, avatar_url VARCHAR(500) DEFAULT NULL, is_blocked TINYINT(1) NOT NULL, locale VARCHAR(10) DEFAULT \'en\' NOT NULL, theme VARCHAR(10) DEFAULT \'light\' NOT NULL, created_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0E3BD61CE16BA31DBBF396750 (queue_name, available_at, delivered_at, id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C9EEA759 FOREIGN KEY (inventory_id) REFERENCES inventory (id)');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526CF675F31B FOREIGN KEY (author_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE inventory ADD CONSTRAINT FK_B12D4A3661220EA6 FOREIGN KEY (creator_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE inventory ADD CONSTRAINT FK_B12D4A3612469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE inventory_tag ADD CONSTRAINT FK_7AE3D8E79EEA759 FOREIGN KEY (inventory_id) REFERENCES inventory (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE inventory_tag ADD CONSTRAINT FK_7AE3D8E7BAD26311 FOREIGN KEY (tag_id) REFERENCES tag (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE inventory_writers ADD CONSTRAINT FK_592B2B239EEA759 FOREIGN KEY (inventory_id) REFERENCES inventory (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE inventory_writers ADD CONSTRAINT FK_592B2B23A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE item ADD CONSTRAINT FK_1F1B251E9EEA759 FOREIGN KEY (inventory_id) REFERENCES inventory (id)');
        $this->addSql('ALTER TABLE item ADD CONSTRAINT FK_1F1B251EB03A8386 FOREIGN KEY (created_by_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE `like` ADD CONSTRAINT FK_AC6340B3A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE `like` ADD CONSTRAINT FK_AC6340B3126F525E FOREIGN KEY (item_id) REFERENCES item (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526C9EEA759');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526CF675F31B');
        $this->addSql('ALTER TABLE inventory DROP FOREIGN KEY FK_B12D4A3661220EA6');
        $this->addSql('ALTER TABLE inventory DROP FOREIGN KEY FK_B12D4A3612469DE2');
        $this->addSql('ALTER TABLE inventory_tag DROP FOREIGN KEY FK_7AE3D8E79EEA759');
        $this->addSql('ALTER TABLE inventory_tag DROP FOREIGN KEY FK_7AE3D8E7BAD26311');
        $this->addSql('ALTER TABLE inventory_writers DROP FOREIGN KEY FK_592B2B239EEA759');
        $this->addSql('ALTER TABLE inventory_writers DROP FOREIGN KEY FK_592B2B23A76ED395');
        $this->addSql('ALTER TABLE item DROP FOREIGN KEY FK_1F1B251E9EEA759');
        $this->addSql('ALTER TABLE item DROP FOREIGN KEY FK_1F1B251EB03A8386');
        $this->addSql('ALTER TABLE `like` DROP FOREIGN KEY FK_AC6340B3A76ED395');
        $this->addSql('ALTER TABLE `like` DROP FOREIGN KEY FK_AC6340B3126F525E');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE comment');
        $this->addSql('DROP TABLE inventory');
        $this->addSql('DROP TABLE inventory_tag');
        $this->addSql('DROP TABLE inventory_writers');
        $this->addSql('DROP TABLE item');
        $this->addSql('DROP TABLE `like`');
        $this->addSql('DROP TABLE tag');
        $this->addSql('DROP TABLE `user`');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
