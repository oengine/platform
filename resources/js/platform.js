import { PlatformUtil } from "./utils/platform.util";

export class ModulePlatform {
  $module = {};
  $loaded = false;
  $utils = PlatformUtil;
  register(name, $_module) {
    this.$module[name] = $_module;
    if (this.$loaded && this.$module[name]) {
      this.$module[name].manager = this;
      try {
        if (this.$module[name].init) {
          this.$module[name].init();
        }
      } catch (ex) {
        console.log("init", name, ex);
      }
      try {
        if (this.$module[name].loading) {
          this.$module[name].loading();
        }
      } catch (ex) {
        console.log("loading", name, ex);
      }
    }
  }
  find($name) {
    return this.$module[$name];
  }
  init() {
    Object.keys(this.$module).forEach((name) => {
      this.$module[name].manager = this;
      try {
        if (this.$module[name].init) {
          this.$module[name].init();
        }
      } catch (ex) {
        console.log("init", name, ex);
      }
    });
    return this;
  }
  loading() {
    Object.keys(this.$module).forEach((name) => {
      setTimeout(() => {
        try {
          if (this.$module[name].loading) {
            this.$module[name].loading();
          }
        } catch (ex) {
          console.log("loading", name, ex);
        }
      });
    });
    this.loaded = true;
  }
  uninit() {
    Object.keys(this.$module).forEach((name) => {
      setTimeout(() => {
        try {
          if (this.$module[name].uninit) {
            this.$module[name].uninit();
          }
        } catch (ex) {
          console.log("uninit", name, ex);
        }
      });
    });
    this.loaded = true;
  }
  restart() {
    this.uninit();
    this.start();
  }
  start() {
    window.addEventListener("DOMContentLoaded", function () {
      this.init().loading();
    });
  }
}
