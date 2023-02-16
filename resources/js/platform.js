import { Event } from "./event";

export class ModulePlatform extends Event {
  $config = {};
  $module = {};
  $loaded = false;

  getCsrfToken() {
    const tokenTag = document.head.querySelector('meta[name="csrf-token"]');

    if (tokenTag && tokenTag.content) {
      return tokenTag.content;
    }

    return window.livewire_token ?? ModulePlatform.$config["csrf_token"];
  }

  request(url, option = {}) {
    let csrfToken = this.getCsrfToken();
    return fetch(url, {
      credentials: "same-origin",
      ...option,
      headers: {
        "Content-Type": "application/json",
        Accept: "text/html, application/xhtml+xml",
        "O-PLATFORM": true,
        ...(option?.headers ?? {}),
        Referer: window.location.href,
        ...(csrfToken && { "X-CSRF-TOKEN": csrfToken }),
        // ...(socketId && { 'X-Socket-ID': socketId })
      },
    });
  }

  htmlToElement(html) {
    var template = document.createElement("template");
    // html = html.trim(); // Never return a text node of whitespace as the result
    template.innerHTML = html;
    return template.content.firstChild;
  }
  htmlToElements(html) {
    var template = document.createElement("template");
    template.innerHTML = html;
    return template.content.childNodes;
  }
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
