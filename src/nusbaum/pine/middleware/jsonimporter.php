<?php
namespace pine\middleware;
use nusbaum\pine\str;
use nusbaum\pine\Request;
use nusbaum\pine\Response;

class JsonImporter {
	/**
	 * 
	 * @param Request $req
	 * @param Response $res
	 */
	public function __invoke($req,$res,$next) {
		// set the hasJson flag
		if($req->isPost() && str::endsWith($req->contentType,'json')) {
			$content = json_decode(file_get_contents('php://input'),1);
			if($content && is_array($content)) {
				foreach ($content as $name => $value) {
					$req->parameters->set($name, $value);
				}
			}
		}
		$next();
	}
}