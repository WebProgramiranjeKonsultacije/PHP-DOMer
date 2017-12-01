<?php
/**
* PHP DOM
*
* This function allow you to render HTML page only via PHP on few simple line of codes.
* With this you can build any web page and avoid bunch of HTML tags.
* Rendering is simple, clean and there is no problems regarding unclosed tags, bad formats etc.
*
* @author    Ivijan-Stefan Stipic <creativform@gmail.com>
* @version   1.0.0
*
* @pharam $version      string   -HTML version like: html5, html1, html 4.01, etc. (default html5)
* @pharam $charset      string   -Charset like: UTF-8 (optional)
* @pharam $lang         string   -Language like: en, de, rs, nl... (optional but important)
*
* EXAMPLE
---------------------------
	// Initialize DOM
	$dom = new DOMer('html5');
	
	// Include CSS
	$dom->css(array(
		'https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css',
	));
	
	// Include JavaScripts
	$dom->js(array(
		'https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js',
		'https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js',
		'https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js',
	));
	
	// Title of the web page
	$dom->title = 'Home Page';
	
	// Initialize content
	$html = $col = $row = array();
	
	// Set columns
	$col[] = $dom->tag('div', array('class'=>'col'), 'Column 1');
	$col[] = $dom->tag('div', array('class'=>'col'), 'Column 2');
	$col[] = $dom->tag('div', array('class'=>'col'), 'Column 3');
	$col[] = $dom->tag('div', array('class'=>'col'), 'Column 4');
	
	// Set row
	$row[] = $dom->tag('div', array('class'=>'row'),$col);
	
	// Set container
	$html[] = $dom->tag('div', array('class'=>'container'),$row);
	
	// Print HTML
	$dom->html($html, array(
		'body_attr' => array(
			'class' => 'home-page',
			'id' => 'home-page'
		),
		'beautify' => true
	),true);
**/
if(!class_exists('DOM')):
class DOMer
{
	public $doctype = '';
	public $charset = '';
	public $lang = '';
	public $title = '';
	public $head = '';
	public $js = '';
	public $css = '';
	
	private $head_default = '';
	
	function __construct($version='html5',$charset='UTF-8',$lang='en')
	{
		$this->lang=$lang;
		$this->charset=$charset;
		$this->set_version($version);
	}
	
	
	/**
	* Create properly formatted HTML tag
	*
	* @pharam $tag      string         -HTML Tag name (div, ul, li, span, strong, etc.)
	* @pharam $attr     array/bool     -Array of HTML attributes and values (same as $this-array())
	* @pharam content   string/array   -HTML or text content inside tag
	* @return           string         -Formatted HTML tag
	*
	* EXAMPLE
	---------------------------
		DOM->tag('div',array(
			'class' => 'col-sm-6 col-md-4',
			'id'	=> 'column_1'
		), 'This is content inside column. This also can be array of contents.');
	**/
	public function tag($tag, $attr=false, $content=NULL)
	{
		$tag = strtolower($tag);
		
		if(is_array($attr))
			$attr = $this->attr($attr);
		else
			$attr = '';
			
		if(in_array($tag, array('input','meta','br','hr','img','embed'),true)!==false)
			$element = "\t<{$tag}{$attr} />";
		else
		{
			$content = PHP_EOL.(is_array($content) ? join(PHP_EOL,$content) : $content).PHP_EOL."\t";
			$element = "\t<{$tag}{$attr}>{$content}</{$tag}>";
		}
		
		unset($content);
		unset($attr);
		unset($tag);
		
		return $element;
	}
	
	/**
	* Create properly formatted attributes for HTML tag
	*
	* @pharam $attr   array   -Array of attributes and it's values
	* @return         string  -Formated attributes
	*
	* EXAMPLE
	---------------------------
		DOM->attr(array(
			'class' => 'col-sm-6 col-md-4',
			'id'	=> 'column_1'
		));
	**/
	public function attr(array $attr=array())
	{
		$return=array();
		
		if(is_array($attr))
		{
			foreach($attr as $attribute => $value)
			{				
				if(preg_match("/([a-z\-]+)/i",$attribute))
				{
					$attribute = trim($attribute);
					$attribute = strtolower($attribute);
					$value = trim($value);
					
					$return[]= "{$attribute}=\"{$value}\"";
				}
			}
			
			unset($attribute);
			unset($value);
			unset($attr);
			
			sort($return);
			
			if(count($return)>0)
			{
				$return = join(" ",$return);
				return " {$return}";
			}
		}
		
		return '';
	}
	
	/**
	* Render proper class attribute and it's values
	*
	* @pharam $attr   array   -Array of classes
	* @return         string  -Formated class attribute
	*
	* EXAMPLE
	---------------------------
		DOM->attr_class(array(
			'col-sm-12',
			'col-md-6',
			'col-lg-4'
		));
	**/
	public function attr_class(array $class=array())
	{
		$return=array();
		if(is_array($class))
		{
			$class = array_map("trim",$class);
			$class = array_filter($class);
			foreach($class as $value)
			{
				$return[]=$value;
			}
			
			unset($value);
			unset($class);
			
			if(count($return)>0)
			{
				$return = join(" ",$return);
				return " class=\"{$return}\"";
			}
		}
		
		return '';
	}
	
	/**
	* Include javascripts
	*
	* @pharam $js     array/string   -Array of scripts or only one javascript file or URL.
	* @return         string  -Formated class attribute
	*
	* EXAMPLE
	---------------------------
		DOM->js(array(
			'https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js',
			'https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js',
			'https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js',
		));
		
		...OR...
		
		$dom->js('https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js');
		
	**/
	public function js($js)
	{
		$this->js = $this->set_script($js);
	}
	
	/**
	* Include CSS stylesheets
	*
	* @pharam $css    array/string   -Array of scripts or only one CSS file or URL.
	*
	* EXAMPLE
	---------------------------
		DOM->css(array(
			'https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css',
		));
		
		...OR...
		
		$dom->css('https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css');
		
	**/
	public function css($css)
	{
		$this->css = $this->set_styles($css);
	}
	
	/**
	* Render complete HTML web page
	*
	* @pharam $html   array/string   -HTML or text content inside body tag
	* @pharam $setup  array          -Setup for that web page
	* @pharam $echo   bool           -If is true, returns DOM via echo
	*
	* EXAMPLE
	---------------------------
		DOM->html('Content inside body tag. It accept also array.',array(
			'compress' => true,
			'body_attr' => array(
				'class' => 'home-page',
				'id' => 'home-page'
			)
		), true);
	**/
	public function html($html, array $setup=array(),bool $echo=false)
	{
		if($echo===true)
		{
			echo $this->set_html($html, $setup);
			
			unset($html);
			unset($attr);
		}
		else
			return $this->set_html($html, $setup); 
	}

/****************** PRIVATE ZONE ******************/

	/* Render styles */
	private function set_styles($css)
	{
		if(is_array($css))
		{
			$arr = array();
			foreach($css as $link)
			{
				$arr[] = $this->include_style($link, '', '', false);
			}
			unset($link);
			return $arr;
		}
		else
		{
			return $this->include_style($css, '', '', false);
		}
	}
	
	/* Render scripts */
	private function set_script($js)
	{
		if(is_array($js))
		{
			$arr = array();
			foreach($js as $link)
			{
				$arr[] = $this->include_script($link, '', '', false);
			}
			unset($link);
			return $arr;
		}
		else
		{
			return $this->include_script($js, '', '', false);
		}
	}

	/* Include scripts */
	private function include_script(string $url, string $version='', string $path='', bool $echo=true){
		$id = '';
		if(filter_var($url, FILTER_VALIDATE_URL)===false)
		{
			if(empty($path))
				$path = $url;
				
			$version = preg_replace("/[^0-9\.\_]/Ui","",$version);
			
			$id = '';
			if(file_exists($path) || file_exists(dirname(__FILE__).$path) || file_exists(dirname(__FILE__).'/'.$path))
			{
				$id = sprintf("?version=%d.%d.%d",filemtime($path),filesize($path),strlen($path));
			}
			else
			{
				if(!empty($version))
					$id = sprintf("?version=%s.%d",$version,strlen($url));
			}
		}
		else
		{
			if(!empty($version))
					$id = sprintf("?version=%s.%d",$version,strlen($url));
		}
		
		$url = $url.$id;
		if($echo === true)
			printf('<script src="%s"></script>%s',$url,PHP_EOL);
		else
			return sprintf('<script src="%s"></script>%s',$url,'');
	}
	
	/* Include styles */
	private function include_style(string $url, string $version='', string $path='', bool $echo=true){
		$id = '';
		if(filter_var($url, FILTER_VALIDATE_URL)===false)
		{
			if(empty($path))
				$path = $url;
				
			$version = preg_replace("/[^0-9\.\_]/Ui","",$version);
			if(file_exists($path) || file_exists(dirname(__FILE__).$path) || file_exists(dirname(__FILE__).'/'.$path))
			{
				$id = sprintf("?version=%d.%d.%d",filemtime($path),filesize($path),strlen($path));
			}
			else
			{
				if(!empty($version))
					$id = sprintf("?version=%s.%d",$version,strlen($url));
			}
		}
		else
		{
			if(!empty($version))
				$id = sprintf("?version=%s.%d",$version,strlen($url));
		}
		
		$url = $url.$id;
		if($echo === true)
			printf('<link href="%s" rel="stylesheet">%s',$url,PHP_EOL);
		else
			return sprintf('<link href="%s" rel="stylesheet">%s',$url,'');
	}

	/* Render HTML */
	private function set_html($html, array $attr=array())
	{
		$attr = array_replace(array(
			'body_attr' => array(),
			'head' => '',
			'head_attr' => array(),
			'html_attr' => array(),
			'title' => '',
			'beautify' => false,
			'compress' => false,
		),$attr);

		ob_start(); ?>
<?=$this->doctype; ?>
<html lang="<?=$this->lang; ?>"<?=$this->attr($attr['html_attr']); ?>>
<head<?=$this->attr($attr['head_attr']); ?>>
<?=(is_array($this->head_default) ? join(PHP_EOL, $this->head_default) : $this->head_default); ?>
<title><?=!empty($attr['title']) ? $attr['title'] : $this->title; ?></title>
<?=(is_array($this->head) ? join(PHP_EOL, $this->head) : $this->head); ?>
<?=(is_array($this->css) ? join(PHP_EOL."\t", $this->css) : $this->css); ?>
<?=(is_array($attr['head']) ? join(PHP_EOL, $attr['head']) : $attr['head']); ?>
</head>
<body<?=$this->attr($attr['body_attr']); ?>>
<?=(is_array($html) ? join(PHP_EOL."\t", $html) : $html); ?>
<?=(is_array($this->js) ? join(PHP_EOL."\t", $this->js) : $this->js); ?>
</body>
</html>
		<?php $html = trim(ob_get_clean());
		
		if($attr['beautify'] === true)
		{
			$dom = new DOMDocument();
			$dom->preserveWhiteSpace = false;
			$dom->loadHTML($html,LIBXML_HTML_NOIMPLIED);
			$dom->formatOutput = true;			
			return $this->doctype.PHP_EOL.$dom->saveHTML($dom->documentElement);
		}
		else if($attr['compress'] === true)
			return preg_replace("/(\t|\r|\n)/","",$html);
		else
			return $html;
	}

	/* Set HTML versions */
	private function set_version(string $version)
	{
		$version = strtolower($version);
		
		$this->head_default = array(
			'<meta http-equiv="Content-Type" content="text/html; charset='.$this->charset.'" />',
			'<meta http-equiv="X-UA-Compatible" content="IE=edge">',
			'<meta name="viewport" content="width=device-width, initial-scale=1.0">'
		);
		
		if(in_array($version,array('5.0','5','html5','html 5','latest')))
		{
			$this->doctype = '<!DOCTYPE html>';
			$this->head_default = array(
				'<meta charset="'.$this->charset.'">',
				'<meta http-equiv="X-UA-Compatible" content="IE=edge">',
				'<meta name="viewport" content="width=device-width, initial-scale=1">'
			);
		}
		else if(in_array($version,array('html 4.01 strict','4.01 strict')))
			$this->doctype = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">';
		else if(in_array($version,array('html4','4','4.01','html 4.01 transitional','4.01 transitional')))
			$this->doctype = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">';
		else if(in_array($version,array('html 4.01 frameset','4.01 frameset')))
			$this->doctype = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN" "http://www.w3.org/TR/html4/frameset.dtd">';
		else if(in_array($version,array('xhtml 1.0 strict','html 1.0 strict','1.0 strict')))
			$this->doctype = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">';
		else if(in_array($version,array('xhtml1','html1','1','1.0','xhtml 1.0','xhtml 1.0 transitional','html 1.0 transitional','1.0 transitional')))
			$this->doctype = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">';
		else if(in_array($version,array('xhtml 1.0 frameset','html 1.0 frameset','1.0 frameset')))
			$this->doctype = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Frameset//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-frameset.dtd">';
		else if(in_array($version,array('xhtml 1.1','html 1.1','1.1')))
			$this->doctype = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">';
	}
}
endif;