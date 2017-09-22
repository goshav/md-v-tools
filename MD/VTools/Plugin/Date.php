<?php

namespace MD\VTools\Plugin;

use EApp\Plugin\Shortable;
use EApp\Proto\Plugin;
use MD\VTools\Traits\PluginShortTagTrait;

class Date extends Plugin implements Shortable
{
	use PluginShortTagTrait;

	public function getContent()
	{
		$format = $this->shortTag ? $this->choice([0, 'format']) : $this->get('format');
		if( !$format )
		{
			return '';
		}

		$time = $this->shortTag ? $this->choice([1, 'time']) : $this->get('time');
		if( !$time )
		{
			$time = time();
		}
		else if( $time instanceof \DateTime )
		{
			return $this->result( $time->format( $format ) );
		}
		else if( !is_numeric($time) )
		{
			$time = strtotime($time);
			if( !$time )
			{
				$time = time();
			}
		}

		return $this->result( @ date( $format, $time ) );
	}

	protected function result( $date )
	{
		if( !strlen($date) )
		{
			return '';
		}

		if( $this->hasShortTag() )
		{
			return $date;
		}
		else
		{
			return \Els::View()->getTpl( $this->get("tpl"), ["date" => $date] );
		}
	}
}