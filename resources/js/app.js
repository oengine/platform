import "whatwg-fetch";
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
