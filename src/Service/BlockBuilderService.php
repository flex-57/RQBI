<?php

namespace App\Service;

use App\Factory\BlockFactoryInterface;
use App\Entity\Enums\BlockTypeEnum;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class BlockBuilderService
{
    public function __construct(
        private BlockFactoryInterface $factory,
        private ValidatorInterface $validator
    ) {}

    /**
     * @param array<string, mixed> $data
     * @return object
     * @throws \RuntimeException
     */
    public function build(BlockTypeEnum $type, array $data): object
    {
        $block = $this->factory->create($type, $data);
        $errors = $this->validator->validate($block);

        if (count($errors) > 0) {
            $messages = [];
            foreach ($errors as $err) {
                $messages[] = sprintf('%s: %s', $err->getPropertyPath(), $err->getMessage());
            }
            throw new \RuntimeException('Invalid block: '.implode('; ', $messages));
        }

        return $block;
    }
}
