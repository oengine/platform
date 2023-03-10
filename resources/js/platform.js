import { Event } from "./event";

export class ModulePlatform extends Event {
  $config = {};
  $module = {};
  $loaded = false;
  $debug = false;
  getCsrfToken() {
    if (this.$config["csrf_token"]) return this.$config["csrf_token"];
    const tokenTag = document.head.querySelector('meta[name="csrf-token"]');

    if (tokenTag && tokenTag.content) {
      return tokenTag.content;
    }

    return window.livewire_token;
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
  appendHtmlToBody(html) {
    const elHtml = this.htmlToElement(html);
    if (document.body) {
      document.body.appendChild(elHtml);
    }
    return elHtml;
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
  addError(error, component = "platform", meta = {}) {
    this.addMessage(error, "error", component, meta);
  }
  addInfo(message, component = "platform", meta = {}) {
    this.addMessage(message, "info", component, meta);
  }
  addMessage(message, type, component = "platform", meta = {}) {
    this.dispatch("platform::message", {
      message,
      type,
      component,
      meta,
    });
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
    self.dispatch("platform::loaded", self);
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
    self.on("platform::message", (data) => {
      if (self.$debug == true) {
        console.log(data);
      }
    });
  }
}
export const modulePlatform = new ModulePlatform();
