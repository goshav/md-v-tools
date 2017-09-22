<?php

namespace MD\VTools\Plugin;

use EApp\App;
use EApp\Plugin\Shortable;
use EApp\Proto\Plugin;
use MD\VTools\Traits\PluginShortTagTrait;

class Test extends Plugin implements Shortable
{
	use PluginShortTagTrait;

	public function getContent()
	{
		if( ! $this->getIs('subject') || ! $this->getIs('then') )
		{
			return '';
		}

		$subject  = $this->prepareGet('subject');
		$then     = $this->get('then');
		$else     = $this->getOr('else', '');
		$operand  = $this->prepareGet('operand');
		$operator = $this->getIs('operator') ? $this->get('operator') : ($operand === false ? 'notEmpty' : 'eq');

		// `subject`  = `[[+total]]`
		// `operator` = `eq`
		// `operand`  = `3`
		// `then`     = `You have more than 3 items!`
		// `else`     = `You're not George. Go away.`

		$operator = strtolower(trim($operator));
		switch ($operator)
		{
			case '!=':
			case 'neq':
			case 'not':
			case 'isnot':
			case 'isnt':
			case 'unequal':
			case 'notequal':
				$output = (($subject != $operand) ? $then : $else);
				break;
			case '<':
			case 'lt':
			case 'less':
			case 'lessthan':
				$output = (($subject < $operand) ? $then : $else);
				break;
			case '>':
			case 'gt':
			case 'greater':
			case 'greaterthan':
				$output = (($subject > $operand) ? $then : $else);
				break;
			case '<=':
			case 'lte':
			case 'lessthanequals':
			case 'lessthanorequalto':
				$output = (($subject <= $operand) ? $then : $else);
				break;
			case '>=':
			case 'gte':
			case 'greaterthanequals':
			case 'greaterthanequalto':
				$output = (($subject >= $operand) ? $then : $else);
				break;
			case 'isempty':
			case 'empty':
				$output = empty($subject) ? $then : $else;
				break;
			case '!empty':
			case 'notempty':
			case 'isnotempty':
				$output = !empty($subject) && $subject != '' ? $then : $else;
				break;
			case 'isnull':
			case 'null':
				$output = $subject == null || strtolower($subject) == 'null' ? $then : $else;
				break;
			case 'inarray':
			case 'in_array':
			case 'ia':
				if( ! is_array($operand) )
				{
					$operand = is_string($operand) ? explode(',', $operand) : [$operand];
				}
				$output = in_array($subject, $operand) ? $then : $else;
				break;
			case '==':
			case '=':
			case 'eq':
			case 'is':
			case 'equal':
			case 'equals':
			case 'equalto':
			default:
				$output = (($subject == $operand) ? $then : $else);
				break;
		}

		return $this->prepareGet($output, true);
	}

	protected function prepareGet( $name, $raw = false )
	{
		$value = $raw ? $name : $this->get($name);
		if( ! $this->getOr('shortTag', true) )
		{
			return $value;
		}

		if( is_string($value) && strpos($value, '{') !== false )
		{
			$value = App::View()->replaceShortTag($value);
		}

		return $value;
	}
}
