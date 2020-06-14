<?php

namespace wherw\entity;

interface MethodStrategyInterface
{
    public function getName($type, string $fileName): ?string;
}