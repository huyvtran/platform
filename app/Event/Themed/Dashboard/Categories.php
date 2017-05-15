<?php

class CategoriesTheme {

	public function after_index($Controller)
	{
		$slug = implode('+', $Controller->viewVars['slugs']);
		if ($slug == 'faq') {
			$Controller->view = 'index_faq';
		}

		$Controller->layout = 'dashboard';
		$Controller->theme = false;
	}
}