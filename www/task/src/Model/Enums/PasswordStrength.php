<?php
namespace Task\Model\Enums;

enum PasswordStrength: string{
    case Weak = "weak";
    case Good = "good";
    case Perfect = "perfect";
}