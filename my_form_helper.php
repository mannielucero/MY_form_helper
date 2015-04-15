<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_form_helper{

    function form_hidden($name, $value = '')
    {
        if ( ! is_array($name))
        {
            $value = (isset($_POST[$name]))?$_POST[$name]:((isset($_GET[$name]))?$_GET[$name]:$value);
            return '<input type="hidden" name="'.$name.'" value="'.form_prep($value).'" />';
        }

        $form = '';
        foreach ($name as $name => $value)
        {
            $value = (isset($_POST[$name]))?$_POST[$name]:((isset($_GET[$name]))?$_GET[$name]:$value);
            $form .= '<input type="hidden" name="'.$name.'" value="'.form_prep($value).'" />';
        }
        
        return $form;
    }

    function form_input($data = '', $value = '', $extra = '')
    {
        $name = ( ! is_array($data))?$data:((isset($data['name']))?$data['name']:'');
        $value = (isset($_POST[$name]))?$_POST[$name]:((isset($_GET[$name]))?$_GET[$name]:$value);
        $defaults = array('type' => 'text', 'name' => (( ! is_array($data)) ? $data : ''), 'value' => $value, 'maxlength' => '500', 'size' => '50');

        return "<input ".parse_form_attributes($data, $defaults).$extra." />\n";
    }

    function form_textarea($data = '', $value = '', $extra = '')
    {
        $name = ( ! is_array($data))?$data:((isset($data['name']))?$data['name']:'');
        $defaults = array('name' => (( ! is_array($data)) ? $data : ''), 'cols' => '90', 'rows' => '12');
        
        if ( ! is_array($data) OR ! isset($data['value']))
        {
            $val = (isset($_POST[$name]))?$_POST[$name]:((isset($_GET[$name]))?$_GET[$name]:$value);
        }
        else
        {
            $val = (isset($_POST[$name]))?$_POST[$name]:((isset($_GET[$name]))?$_GET[$name]:$data['value']); 
            unset($data['value']); // textareas don't use the value attribute
        }
            
        return "<textarea ".parse_form_attributes($data, $defaults).$extra.">".$val."</textarea>\n";
    }

    function form_dropdown($name = '', $options = array(), $selected = '', $extra = '')
        {
            if ($extra != '') $extra = ' '.$extra;
            
            $form = '<select name="'.$name.'"'.$extra.">\n";
            
            foreach ($options as $key => $val)
            {
                $key = (string) $key;
                $val = (string) $val;
                
                $name = str_replace('[]','',$name);
                $send = (isset($_POST[$name]))?$_POST[$name]:((isset($_GET[$name]))?$_GET[$name]:$selected);
               
                $sel = (is_array($send) && in_array($key, $send))?' selected="selected"':(($key == $send)?' selected="selected"':'');
            
                $form .= '<option value="'.$key.'"'.$sel.'>'.$val."</option>\n";
            }

            $form .= '</select>';
        
            return $form;
        }

    function form_checkbox($data = '', $value = '', $checked = FALSE, $extra = '')
    {
        $name = ( ! is_array($data))?$data:((isset($data['name']))?$data['name']:'');
        $defaults = array('type' => 'checkbox', 'name' => (( ! is_array($data)) ? $data : ''), 'value' => $value);
        
        if (is_array($data) AND array_key_exists('checked', $data))
        {
            $checked = (isset($_POST[$name]))?TRUE:((isset($_GET[$name]))?TRUE:$data['checked']);
            
            if ($checked == FALSE)
            {
                unset($data['checked']);
            }
            else
            {
                $data['checked'] = 'checked';
            }
        }
        
        $checked = (isset($_POST[$name]))?TRUE:((isset($_GET[$name]))?TRUE:$checked);
        if ($checked == TRUE)
            $defaults['checked'] = 'checked';
        else
            unset($defaults['checked']);

        return "<input ".parse_form_attributes($data, $defaults).$extra." />\n";
    }

    function form_radio($data = '', $value = '', $checked = FALSE, $extra = '')
        {
            if ( ! is_array($data))
            {    
                $data = array('name' => $data);
            }

            $data['type'] = 'radio';
            return form_checkbox($data, $value, $checked, $extra);
        }    
}
