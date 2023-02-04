export const PlatformUtil = {
  getCsrfToken() {
    const tokenTag = document.head.querySelector('meta[name="csrf-token"]');

    if (tokenTag) {
      return tokenTag.content;
    }

    return window.livewire_token ?? undefined;
  },
  request(url, option = {}) {
    let csrfToken = PlatformUtil.getCsrfToken();
    return fetch(url, {
      credentials: "same-origin",
      ...option,
      headers: {
        "Content-Type": "application/json",
        Accept: "text/html, application/xhtml+xml",
        ...(option?.headers ?? {}),
        Referer: window.location.href,
        ...(csrfToken && { "X-CSRF-TOKEN": csrfToken }),
        // ...(socketId && { 'X-Socket-ID': socketId })
      },
    });
  },

  htmlToElement(html) {
    var template = document.createElement("template");
    html = html.trim(); // Never return a text node of whitespace as the result
    template.innerHTML = html;
    return template.content.firstChild;
  },
  htmlToElements(html) {
    var template = document.createElement("template");
    template.innerHTML = html;
    return template.content.childNodes;
  },
};
