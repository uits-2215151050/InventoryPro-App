<?php

namespace App\Service;

use App\Entity\Inventory;
use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\Uuid;

class CustomIdGenerator
{
    public function __construct(private EntityManagerInterface $em)
    {
    }

    public function generate(Inventory $inventory): string
    {
        $format = $inventory->getCustomIdFormat();

        if (empty($format)) {
            // Default: simple sequence
            return (string) ($inventory->incrementSequenceCounter());
        }

        $parts = [];
        foreach ($format as $element) {
            $parts[] = $this->generateElement($element, $inventory);
        }

        // Update sequence counter if used
        $this->em->flush();

        return implode('', $parts);
    }

    private function generateElement(array $element, Inventory $inventory): string
    {
        $type = $element['type'] ?? 'static';
        $value = $element['value'] ?? '';
        $options = $element['options'] ?? [];

        return match ($type) {
            'static' => $value,
            'random_20bit' => $this->formatNumber(random_int(0, 1048575), $options),
            'random_32bit' => $this->formatNumber(random_int(0, 4294967295), $options),
            'random_6digit' => str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT),
            'random_9digit' => str_pad((string) random_int(0, 999999999), 9, '0', STR_PAD_LEFT),
            'guid' => strtoupper(Uuid::uuid4()->toString()),
            'datetime' => (new \DateTime())->format($options['format'] ?? 'Y-m-d'),
            'sequence' => $this->formatNumber($inventory->incrementSequenceCounter(), $options),
            default => '',
        };
    }

    private function formatNumber(int $value, array $options): string
    {
        $leadingZeros = $options['leadingZeros'] ?? 0;

        if ($leadingZeros > 0) {
            return str_pad((string) $value, $leadingZeros, '0', STR_PAD_LEFT);
        }

        $hex = $options['hex'] ?? false;
        if ($hex) {
            return strtoupper(dechex($value));
        }

        return (string) $value;
    }

    public function generatePreview(array $format): string
    {
        $parts = [];
        foreach ($format as $element) {
            $parts[] = $this->generatePreviewElement($element);
        }
        return implode('', $parts);
    }

    private function generatePreviewElement(array $element): string
    {
        $type = $element['type'] ?? 'static';
        $value = $element['value'] ?? '';
        $options = $element['options'] ?? [];

        return match ($type) {
            'static' => $value,
            'random_20bit' => $this->formatNumber(123456, $options),
            'random_32bit' => $this->formatNumber(1234567890, $options),
            'random_6digit' => '123456',
            'random_9digit' => '123456789',
            'guid' => 'A1B2C3D4-E5F6-7890-ABCD-EF1234567890',
            'datetime' => (new \DateTime())->format($options['format'] ?? 'Y-m-d'),
            'sequence' => $this->formatNumber(1, $options),
            default => '',
        };
    }
}
