<script>
var elements = document.querySelectorAll('a[data-pr-ping-path]');
for (var i = 0; i < elements.length; i++) {
  elements[i].addEventListener('click', function() {
    if (this.dataset != undefined && this.dataset.prPingPath != undefined) {
      var pingPath = this.dataset.prPingPath;
    }
    else {
      var pingPath = this.getAttribute('data-pr-ping-path');
      if (pingPath == undefined) {
        return;
      }
    }
    var destination = this.href;
    var request = new XMLHttpRequest();
    request.open('GET', 'https://<?=MASTER_URI?>/external_link.php?no_redirect=1&' + pingPath, true);
    request.onreadystatechange = function() {
      if (this.readyState === 4) {
        window.URL = destination;
      }
    };
    request.send();
    request = null;
    return false;
  });
}
</script>
