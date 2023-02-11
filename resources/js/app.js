import "whatwg-fetch";
// import 'bootstrap/js/dist/alert';
// import 'bootstrap/js/dist/button';
// import 'bootstrap/js/dist/carousel';
// import 'bootstrap/js/dist/collapse';
// import 'bootstrap/js/dist/dropdown';
import 'bootstrap/js/dist/modal';
// import 'bootstrap/js/dist/offcanvas';
// import 'bootstrap/js/dist/popover';
// import 'bootstrap/js/dist/scrollspy';
// import 'bootstrap/js/dist/tab';
// import 'bootstrap/js/dist/toast';
// import 'bootstrap/js/dist/tooltip';
import Alpine from "alpinejs";
import { ModulePlatform } from "./platform";
import { PlatformComponent } from "./modules/components";
window.Alpine = Alpine;
window.ModulePlatform = new ModulePlatform();
Alpine.start();
window.ModulePlatform.start();
window.ModulePlatform.register(
  "PLATFORM_COMPONENT_MODULE",
  new PlatformComponent()
);
