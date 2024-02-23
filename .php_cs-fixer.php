<?php
$header = <<<'EOF'
    This file is part of JSRunner by Lanre.

    @link     https://github.com/oplanre/jsrunner
    @document https://github.com/oplanre/jsrunner/blob/master/README.md
    @license  https://github.com/oplanre/jsrunner/blob/master/LICENSE
    EOF;

return (new PhpCsFixer\Config())
    ->setRiskyAllowed(true)
    ->setRules([
        'phpdoc_scalar' => true,
        'phpdoc_to_param_type' => true,
        'phpdoc_to_property_type' => true,
        'phpdoc_to_return_type' => true,
        // '@PSR2' => true,
        // '@Symfony' => true,
        // '@DoctrineAnnotation' => true,
        '@PhpCsFixer' => true,
        'header_comment' => [
            'comment_type' => 'PHPDoc',
            'header' => $header,
            'separate' => 'none',
            'location' => 'after_declare_strict',
        ],
        'array_syntax' => [
            'syntax' => 'short',
        ],
        'list_syntax' => [
            'syntax' => 'short',
        ],
        'concat_space' => [
            'spacing' => 'one',
        ],
        'blank_line_before_statement' => [
            'statements' => [
                'declare',
            ],
        ],
        'general_phpdoc_annotation_remove' => [
            'annotations' => [
                'author',
                'param',
                'return',
                'template',
            ],
        ],
        'ordered_imports' => [
            'imports_order' => [
                'class', 'function', 'const',
            ],
            'sort_algorithm' => 'alpha',
        ],
        'single_line_comment_style' => [
            'comment_types' => [
            ],
        ],
        'yoda_style' => [
            'always_move_variable' => false,
            'equal' => false,
            'identical' => false,
        ],
        'phpdoc_align' => [
            'align' => 'left',
        ],
        'multiline_whitespace_before_semicolons' => [
            'strategy' => 'no_multi_line',
        ],
        'constant_case' => [
            'case' => 'lower',
        ],
        'class_attributes_separation' => true,
        'combine_consecutive_unsets' => true,
        'declare_strict_types' => true,
        'linebreak_after_opening_tag' => true,
        'lowercase_static_reference' => true,
        'no_useless_else' => true,
        'no_unused_imports' => true,
        'not_operator_with_successor_space' => true,
        'not_operator_with_space' => false,
        'ordered_class_elements' => true,
        'php_unit_strict' => false,
        'phpdoc_separation' => false,
        'single_quote' => true,
        'standardize_not_equals' => true,
        'multiline_comment_opening_closing' => true,

        'assign_null_coalescing_to_coalesce_equal' => true,
        'clean_namespace' => true,
        'fully_qualified_strict_types' => false,
        'global_namespace_import' => [
            'import_constants' => true,
            'import_functions' => true,
        ],
        'group_import' => false,
        'heredoc_indentation' => true,
        'method_argument_space' => true,
        'no_empty_phpdoc' => true,
        'no_superfluous_phpdoc_tags' => true,
        'no_trailing_whitespace_in_comment' => true,
        'no_unneeded_control_parentheses' => true,
        'no_unneeded_curly_braces' => true,
        'no_unneeded_import_alias' => true,
        'no_unset_cast' => true,
        'no_whitespace_before_comma_in_array' => true,
        'normalize_index_brace' => true,
        'octal_notation' => true,
        'phpdoc_annotation_without_dot' => true,
        'phpdoc_no_useless_inheritdoc' => true,
        'phpdoc_order_by_value' => true,
        'phpdoc_trim' => true,
        'short_scalar_cast' => true,
        'simple_to_complex_string_variable' => true,
        'ternary_to_null_coalescing' => true,
        'trailing_comma_in_multiline' => true,
        'visibility_required' => true,
        'void_return' => true,
    ])
    ->setFinder(
        PhpCsFixer\Finder::create()
            ->exclude('bin')
            ->exclude('vendor')
            ->in(__DIR__ . '/src')
    )
    ->setUsingCache(false);
