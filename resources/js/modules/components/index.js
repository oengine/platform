export class PlatformComponent {
  manager = undefined;
  triggerEventComponent(el) {
    const self = this;
    el.querySelectorAll("platform\\:component]")?.forEach((elItem) => {
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
          let el = self.manager.htmlToElement(data.html);
          toEl.appendChild(el);
          self.triggerEventComponent(el);
          this.manager.dispatch("platform::component", el);
        } else {
          this.manager.dispatch("platform::error", {
            error: response,
            type: "platform::component",
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
    this.manager.addEventListener("platform::component", (el) => {
      self.triggerEventComponent(el);
    });
  }
  loading() {
    const self = this;
    self.triggerEventComponent(document?.body);
  }
  unint() {}
}
