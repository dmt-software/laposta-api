# Fields 

## Numeric fields

> NOTE: Numeric fields are considered to be a float by default.
> To force the field to be an integer set the `defaultvalue` for that field to an integer.

## Creating select fields

> NOTE: optionsFull are only available on update, not when creating a field.

## Modifying options

Always use the options_full when the options need to change for a field.
To add one option to the existing options of a field, you need to post the existing options and add an extra option.
For this option the id can be empty. When more than one option is to be added to the field options, the new options 
can be added by setting an unique negative id.

> NOTE: On update the options array will be ignored, to ensure a more predictable result.
