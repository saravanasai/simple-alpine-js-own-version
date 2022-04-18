<html>
  <div s-data="{count:0,name:'updating after two days'}">
    <button type="button" $click="count++">increase</button>
    <button type="button" $click="name='saravana sai'">increase</button>
    <div s-text="count"></div>
    <div s-text="name"></div>
    <button type="button" $click="count--">Decrease</button>
  </div>

  <script>
    let root = document.querySelector("[s-data]");

    let rawData = getData(root);

    let data = Observe(rawData);

    registerListeners();
    reloadDom();

    function registerListeners() {
      walkDom(root, (el) => {
        if (el.hasAttribute("$click")) {
          let expression = el.getAttribute("$click");

          el.addEventListener("click", () => {
            eval(`with(data){ (${expression})}`);
          });
        }
      });
    }

    function Observe(data) {
      return new Proxy(data, {
        set(target, key, value) {
          target[key] = value;
          reloadDom();
          return 0;
        },
      });
    }

    function reloadDom() {
      walkDom(root, (el) => {
        if (el.hasAttribute("s-text")) {
          let expression = el.getAttribute("s-text");

          el.innerText = eval(`with(data){ (${expression})}`);
        }
      });
    }

    function walkDom(el, callback) {
      callback(el);

      el = el.firstElementChild;

      while (el) {
        walkDom(el, callback);
        el = el.nextElementSibling;
      }
    }
    //part where get back the data object
    function getData(root) {
      let data = root.getAttribute("s-data");

      return eval(`(${data})`);
    }
  </script>
</html>
