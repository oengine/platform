export class PlatformComponent {
  manager = undefined;
  triggerEventComponent(el) {
    const self = this;
    el.querySelectorAll("[platform\\:component]").forEach((elItem) => {
      elItem.removeEventListener(
        "click",
        self.clickEventComponent.bind(self),
        true
      );
      elItem.addEventListener("click", self.clickEventComponent.bind(self));
    });
  }
  openComponent(key, toEl) {
    const self = this;
    if (!toEl) toEl = document?.body;
    this.manager
      .request(self.manager.getUrl("component"), {
        method: "post",
        body: JSON.stringify({
          key: key,
        }),
      })
      .then(async (response) => {
        if (response.ok) {
          let data = await response.json();
          if (!data.error_code) {
            let el = self.manager.htmlToElement(data.html);
            toEl.appendChild(el);
            self.triggerEventComponent(el);
            this.manager.dispatch("platform::component", el);
          } else {
            this.manager.dispatch("platform::error", {
              error: response,
              type: "platform::component",
              toEl,
              key,
            });
          }

          if (data.csrf_token)
            this.manager.$config["csrf_token"] = data.csrf_token;
        } else {
          this.manager.dispatch("platform::error", {
            error: response,
            type: "platform::component",
            toEl,
            key,
          });
        }
      });
  }
  clickEventComponent(e) {
    const self = this;
    let elComponent = e.currentTarget;
    let strComponent = elComponent.getAttribute("platform:component");
    let targetTo = elComponent.getAttribute("component:target");
    if (targetTo) {
      try {
        targetTo = document.querySelector(targetTo);
      } catch {
        targetTo = undefined;
      }
    }
    if (!targetTo) {
      targetTo = document?.body;
    }
    this.openComponent(strComponent, targetTo);
  }
  init() {
    const self = this;
    this.manager.on("platform::component", (el) => {
      self.triggerEventComponent(el);
    });
    this.manager.on("platform::error", (error) => {
      console.log(error);
    });
  }
  loading() {
    const self = this;
    self.triggerEventComponent(document?.body);
  }
  unint() {}
}
