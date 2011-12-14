##Examples

If you application uses a modular approach, the recommended usage would be to register your listeners inside the `init.php` file. 
Also, the most straight forward way is to have static methods as handlers.

Let's say that you have a module `your-module` and want to add items to the main menu.
You have a Menu class that handles the creation and redering. The only thing you need to 
do is add items dynamically.

You would register a listener in your module's init file. 

Excerpt from `your-module/init.php`

	Dispatcher::instance()->add_listener('menu_build','Controller_Pages::build_menu');


In the handler method, you would have access to the menu, to add your items.

Excerpt from `your-module/classes/controller/pages.php`

	static public function build_menu(Event $e)
	{
		$menu = $e->menu;
		$menu->add_child('Home','/home');
		$menu->add_child('About','/about');
		
		$portfolio = $menu->add_child('Portfolio','/portfolio');
		$portfolio->add_child('Web','/web');
		$portfolio->add_child('Print','/print');		
		
	}

Excerpt from `application/views/layout.php`
	
	<body>
		<div id="wrapper">
			<nav>
			<?php 
				$event = new Event('menu_build');
				$event->bind("menu",$menu);
				Dispatcher::instance()->dispatch_event($event);
				echo $menu->render();
			?>
			</nav>
			<div id="content">...</div>
		</div>
	</body>
	