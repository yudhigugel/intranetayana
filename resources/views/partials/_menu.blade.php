
<div id="sideMenuComponent">
    <side-menu :user_menus='  <?= json_encode(app("side_menu")); ?>' current_route="{{Route::getFacadeRoot()->current()->uri()}}"></side-menu>
</div>