<?php

namespace App\Form\DataTransformer;

use App\Entity\Tag;
use App\Repository\TagRepository;
use Symfony\Component\Form\DataTransformerInterface;
use function Symfony\Component\String\u;


final readonly class TagArrayToStringTransformer implements DataTransformerInterface
{
    public function __construct(
        private TagRepository $tags
    ) {
    }

    public function transform($tags): string
    {
        return implode(' ', $tags);
    }

    public function reverseTransform($string): array
    {
        if (null === $string || u($string)->isEmpty()) {
            return [];
        }

        $names = array_filter(array_unique($this->trim(u($string)->split(' '))));

        // Get the current tags and find the new ones that should be created.
        $tags = $this->tags->findBy([
            'name' => $names,
        ]);

        $newNames = array_diff($names, $tags);

        foreach ($newNames as $name) {
            $tags[] = new Tag($name);
        }
        // End

        return $tags;
    }

    private function trim(array $strings): array
    {
        $result = [];

        foreach ($strings as $string) {
            $result[] = trim($string);
        }

        return $result;
    }
}
