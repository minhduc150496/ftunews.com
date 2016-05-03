<?php

if ( !defined('ABSPATH') )
    exit; // Shhh

/**
 * Poll Customizer.
 * 
 * @since 2.0.0
 * @package TotalPoll\Customizer
 */
class TP_Poll_Customizer {

    /**
     * Render field.
     * 
     * @since 2.0.0
     * @param string $section_id Section ID
     * @param string $field_id Field ID
     * @param objec $field Field Object
     * @return string Rendered Field
     */
    public function field($section_id, $field_id, $field, $additional_name = '') {

        // No field type? no way
        if (!isset($field->type)):
            return;
        endif;

        // Built-in fields
        $builtin_fields = array('text', 'color', 'textarea', 'select');
        // Sanitize ID
        $field->id = strtolower(str_replace('_', '-', $section_id . '-' . $field_id));
        // Generate a name
        $field->name = "tp_options[template][preset][settings][$section_id][$field_id]$additional_name";
        // Field HTML container
        $field_html = '';

        // Custom field type
        if (!in_array($field->type, $builtin_fields)):
            if (is_callable($field->type)):
                $field_html = call_user_func_array($field->type, array('args' => $field));
            endif;
        // Builtin field type
        else:
            // Render field
            $field_html .= call_user_func_array("TP_Poll_Customizer_Fields::{$field->type}", array('args' => $field));

            // Render other states
            if (isset($field->states) && is_array($field->states)):
                foreach ($field->states as $id => $state):
                    // Unique ID
                    $field->id = $field->id . "-$id";
                    // Pseudoname
                    $field->name = "tp_options[template][preset][settings][$section_id][$field_id:$id]";
                    // Label
                    $field->label = $state['label'];
                    // Value
                    $field->value = isset($state['value']) ? $state['value'] : $state['default'];
                    // To the stack
                    $field_html .= call_user_func_array("TP_Poll_Customizer_Fields::{$field->type}", array('args' => $field));
                endforeach;
            endif;

        endif;

        // Return final HTML
        return $field_html;
    }

}
