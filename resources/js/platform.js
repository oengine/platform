import { PlatformUtil } from "./utils/platform.util";

export class ModulePlatform {
  $config = {};
  $module = {};
  $loaded = false;
  $utils = PlatformUtil;
  getUrl($url) {
    return this.$config["platform_url"] + "/" + $url;
  }
  register(name, $_module) {
    const self = this;
    self.$module[name] = $_module;
    if (self.$loaded && self.$module[name]) {
      self.$module[name].manager = self;
      try {
        if (self.$module[name].init) {
          self.$module[name].init();
        }
      } catch (ex) {
        console.log("init", name, ex);
      }
      try {
        if (self.$module[name].loading) {
          self.$module[name].loading();
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
    const self = this;
    Object.keys(self.$module).forEach((name) => {
      self.$module[name].manager = self;
      try {
        if (self.$module[name].init) {
          self.$module[name].init();
        }
      } catch (ex) {
        console.log("init", name, ex);
      }
    });
    return this;
  }
  loading() {
    const self = this;
    Object.keys(self.$module).forEach((name) => {
      setTimeout(() => {
        try {
          if (self.$module[name].loading) {
            self.$module[name].loading();
          }
        } catch (ex) {
          console.log("loading", name, ex);
        }
      });
    });
    self.$loaded = true;
  }
  uninit() {
    const self = this;
    Object.keys(self.$module).forEach((name) => {
      setTimeout(() => {
        try {
          if (self.$module[name].uninit) {
            self.$module[name].uninit();
          }
        } catch (ex) {
          console.log("uninit", name, ex);
        }
      });
    });
    self.$loaded = true;
  }
  restart() {
    this.uninit();
    this.start();
  }
  start() {
    const self = this;
    window.addEventListener("DOMContentLoaded", function () {
      self.init().loading();
    });
  }
}
