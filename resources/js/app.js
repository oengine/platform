import "whatwg-fetch";
import { ModulePlatform } from "./platform";
import { PlatformComponent } from "./modules/components";
window.ModulePlatform = new ModulePlatform();
window.ModulePlatform.start();
window.ModulePlatform.register(
  "PLATFORM_COMPONENT_MODULE",
  new PlatformComponent()
);
