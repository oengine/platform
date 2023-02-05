export class PlatformComponent {
  manager = undefined;
  triggerEventComponent(el) {
    const self = this;
    el.querySelectorAll("[wire\\:component]")?.forEach((elItem) => {
      console.log(elItem);
      elItem.removeEventListener(
        "click",
        self.clickEventComponent.bind(self),
        true
      );
      elItem.addEventListener("click", self.clickEventComponent.bind(self));
    });
    if (el.classList.contains("modal")) {
    }
  }
  openComponent(key, toEl) {
    const self = this;
    if (!toEl) toEl = document.body;
    this.manager.$utils
      .request(self.manager.getUrl("livewire/component"), {
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
          window.livewire?.rescan();
          self.triggerEventComponent(el);
          window.dispatchEvent(
            new CustomEvent("openComponent", { detail: el })
          );
        } else {
          console.log(response);
        }
      });
  }
  clickEventComponent(e) {
    let elComponent = e.currentTarget;
    let strComponent = elComponent.getAttribute("wire:component");
    let targetTo = elComponent.getAttribute("component:target");
    if (targetTo) {
      try {
        targetTo = document.querySelector(targetTo);
      } catch {}
    }
    if (!targetTo) {
      targetTo = document.body;
    }
    this.openComponent(strComponent, targetTo);
  }
  init() {}
  loading() {
    this.triggerEventComponent(document.body);
  }
  unint() {}
}
