<?php

class ArticlesTheme {

	public function after_view($Controller)
	{
		$article = $Controller->viewVars['article'];
		if ($article['Category']['slug'] == 'faq') {
			$Controller->view = 'view_faq';
		}
	}

}