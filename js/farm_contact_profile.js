(function (Drupal, once) {
    Drupal.behaviors.contactTabs = {
      attach(context, settings) {
        once('contact-tabs', '.tabs', context).forEach(function (tabsContainer) {
          tabsContainer.querySelectorAll('.tab').forEach(function (tab) {
            tab.addEventListener('click', function () {
              tabsContainer.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
              document.querySelectorAll('.content-section').forEach(section => section.classList.remove('active'));
  
              this.classList.add('active');
              const tabId = this.dataset.tab;
              document.getElementById(tabId).classList.add('active');
            });
          });
        });
      }
    };
  })(Drupal, once);
  