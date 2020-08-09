<?php

$finder = PhpCsFixer\Finder::create()
    ->in([__DIR__ . '/app']);


$config = PhpCsFixer\Config::create()
    ->setRiskyAllowed(true)
    ->setRules([
        '@PSR2' => true,
        '@PHP56Migration' => true,
        'array_indentation' => false,
        'array_syntax' => ['syntax' => 'short'],
        'blank_line_before_statement' => false,
        'combine_consecutive_issets' => true,
        'combine_consecutive_unsets' => true,
        'compact_nullable_typehint' => true,
        'escape_implicit_backslashes' => true,
        'explicit_indirect_variable' => true,
        'explicit_string_variable' => true,
        'final_internal_class' => true,
        'fully_qualified_strict_types' => true,
        'function_to_constant' => ['functions' => ['get_class', 'get_called_class', 'php_sapi_name', 'phpversion', 'pi']],
        'heredoc_to_nowdoc' => true,
        'list_syntax' => ['syntax' => 'short'],
        'method_argument_space' => ['ensure_fully_multiline' => true],
        'method_chaining_indentation' => false,
        'multiline_comment_opening_closing' => true,
        'no_alternative_syntax' => true,
        'no_extra_blank_lines' => ['tokens' => ['break', 'continue', 'extra', 'return', 'throw', 'use', 'parenthesis_brace_block', 'square_brace_block', 'curly_brace_block']],
        'no_null_property_initialization' => true,
        'no_short_echo_tag' => true,
        'no_superfluous_elseif' => true,
        'no_unreachable_default_argument_value' => true,
        'no_useless_else' => true,
        'no_useless_return' => true,
        'ordered_imports' => true,
        'php_unit_ordered_covers' => true,
        'php_unit_set_up_tear_down_visibility' => true,
        'php_unit_strict' => true,
        'php_unit_test_annotation' => true,
        'php_unit_test_class_requires_covers' => true,
        'phpdoc_add_missing_param_annotation' => true,
        'semicolon_after_instruction' => true,
        'single_line_comment_style' => true,
        'strict_comparison' => true,
        'strict_param' => true,
        'string_line_ending' => true,
    ])
    ->setFinder($finder);

return $config;
