<?php
/**
 * Created by IntelliJ IDEA.
 * User: GoshaV [Maniako] <gosha@rozaverta.com>
 * Date: 23.09.2017
 * Time: 0:40
 */

namespace MD\VTools\Traits;

trait PluginShortTagTrait
{
	protected $shortTag = false;

	/**
	 * The plugin used a short tag
	 *
	 * @return mixed
	 */
	public function toShortTag()
	{
		$this->shortTag = true;
	}

	/**
	 * Does the plugin use a short tag?
	 * @return boolean
	 */
	public function hasShortTag()
	{
		return $this->shortTag;
	}
}