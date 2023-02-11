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
    if (!toEl) toEl = document.body;
    this.manager.$utils
      .request(self.manager.getUrl("component"), {
        method: "post",
        body: JSON.stringify({
          key: key,
        }),
      })
      .then(async (response) => {
        if (response.ok) {
          let data = await response.json();
          let el = self.manager.$utils.htmlToElement(data.html);
          toEl.appendChild(el);
          window?.livewire?.rescan();
          self.triggerEventComponent(el);
          window.dispatchEvent(new CustomEvent("platform::component", el));
        } else {
          window.dispatchEvent(
            new CustomEvent("platform::error", { response })
          );
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
      targetTo = document.body;
    }
    this.openComponent(strComponent, targetTo);
  }
  init() {
    window?.addEventListener("platform::component", (el) => {
      self.triggerEventComponent(el);
    });
  }
  loading() {
    const self = this;
    self.triggerEventComponent(document.body);
  }
  unint() {}
}
