<?php

App::uses('AppHelper', 'View/Helper');

class FckHelper extends Helper {

	public function load($id, $toolbar = null, $height = 400) {
		if (empty($toolbar)) {
			$toolbar = 'Default';
		}
		foreach (explode('/', $id) as $v) {
			@$did .= ucfirst($v);
		}

		$jsPath = $this->webroot . 'js/';

		return <<<FCK_CODE
		<script type="text/javascript">
			fckLoader_$did = function () {
			var bFCKeditor_$did = new FCKeditor('$did');
			bFCKeditor_$did.BasePath = '$jsPath';
			bFCKeditor_$did.ToolbarSet = '$toolbar';
				bFCKeditor_$did.Skin = 'silver';
					bFCKeditor_$did.Height = '$height';
			
			bFCKeditor_$did.ReplaceTextarea();
			}
			fckLoader_$did();
		</script>
FCK_CODE;
	}

	function load_basic($id, $toolbar = null, $height = 400) {
		if (empty($toolbar)) {
			$toolbar = 'Basic';
		}
		foreach (explode('/', $id) as $v) {
			@$did .= ucfirst($v);
		}
		$jsPath = $this->webroot . 'js/';
		return <<<FCK_CODE
		<script type="text/javascript">
			fckLoader_$did = function () {
			var bFCKeditor_$did = new FCKeditor('$did');
			bFCKeditor_$did.BasePath = '$jsPath';
			bFCKeditor_$did.ToolbarSet = '$toolbar';
				bFCKeditor_$did.Skin = 'silver';
					bFCKeditor_$did.Height = '$height';
			
			bFCKeditor_$did.ReplaceTextarea();
			}
			fckLoader_$did();
		</script>
FCK_CODE;
	}

}
