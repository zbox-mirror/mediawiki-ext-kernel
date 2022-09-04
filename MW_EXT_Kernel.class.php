<?php

namespace MediaWiki\Extension\CMFStore;

use OutputPage, PPFrame, RequestContext, Skin;

/**
 * Class MW_EXT_Kernel
 */
class MW_EXT_Kernel
{
  /**
   * Clear DATA (escape html).
   *
   * @param $string
   *
   * @return string
   */
  public static function outClear($string)
  {
    $trim = trim($string);
    $out = htmlspecialchars($trim, ENT_QUOTES);

    return $out;
  }

  /**
   * Normalize DATA (lower case & remove space).
   *
   * @param $string
   *
   * @return string
   */
  public static function outNormalize($string)
  {
    $replace = str_replace(' ', '-', $string);
    $out = mb_strtolower($replace, 'UTF-8');

    return $out;
  }

  /**
   * Get JSON data.
   *
   * @param $src
   *
   * @return mixed
   */
  public static function getJSON($src)
  {
    $src = file_get_contents($src);
    $out = json_decode($src, true);

    return $out;
  }

  /**
   * Wiki Framework: Message.
   *
   * @param $id
   * @param $key
   *
   * @return string
   */
  public static function getMessageText($id, $key)
  {
    $string = 'mw-ext-' . $id . '-' . $key;
    $message = wfMessage($string)->inContentLanguage();
    $out = $message->text();

    return $out;
  }

  /**
   * Wiki Framework: Configuration parameters.
   *
   * @param $config
   *
   * @return mixed
   * @throws \ConfigException
   */
  public static function getConfig($config)
  {
    $context = RequestContext::getMain()->getConfig();
    $out = $context->get($config);

    return $out;
  }

  /**
   * Wiki Framework: Title.
   *
   * @return null|\Title
   */
  public static function getTitle()
  {
    $context = RequestContext::getMain();
    $out = $context->getTitle();

    return $out;
  }

  /**
   * Wiki Framework: User.
   *
   * @return \User
   */
  public static function getUser()
  {
    $context = RequestContext::getMain();
    $out = $context->getUser();

    return $out;
  }

  /**
   * Wiki Framework: WikiPage.
   *
   * @return \WikiPage
   * @throws \MWException
   */
  public static function getWikiPage()
  {
    $context = RequestContext::getMain();
    $out = $context->getWikiPage();

    return $out;
  }

  /**
   * Converts an array of values in form [0] => "name=value" into a real
   * associative array in form [name] => value. If no = is provided,
   * true is assumed like this: [name] => true.
   *
   * @param array $options
   * @param PPFrame $frame
   *
   * @return array
   */
  public static function extractOptions($options = [], PPFrame $frame)
  {
    $results = [];

    foreach ($options as $option) {
      $pair = explode('=', $frame->expand($option), 2);

      if (count($pair) === 2) {
        $name = MW_EXT_Kernel::outClear($pair[0]);
        $value = MW_EXT_Kernel::outClear($pair[1]);
        $results[$name] = $value;
      }

      if (count($pair) === 1) {
        $name = MW_EXT_Kernel::outClear($pair[0]);
        $results[$name] = true;
      }
    }

    return $results;
  }

  /**
   * Load resource function.
   *
   * @param OutputPage $out
   * @param Skin $skin
   *
   * @return bool
   */
  public static function onBeforePageDisplay(OutputPage $out, Skin $skin)
  {
    $out->addModuleStyles(['ext.mw.kernel.styles']);

    return true;
  }
}
