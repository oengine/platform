import "whatwg-fetch";
import { modulePlatform } from "./platform";
import { PlatformComponent } from "./modules/components";
window.ModulePlatform = modulePlatform;
window.ModulePlatform.start();
window.ModulePlatform.register(
  "PLATFORM_COMPONENT_MODULE",
  new PlatformComponent()
);
