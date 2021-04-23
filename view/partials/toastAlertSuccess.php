<?php 
if($_SESSION['success'] === true) {
  ?>
  <div class="position-fixed top-0 start-50 translate-middle-x p-5" style="z-index: 5">
    <div id="liveToast" class="toast hide" role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="5000" data-bs-animation="true">
      <div class="toast-header bg-success text-white">
        <strong class="me-auto">Notification</strong>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>
      </div>
      <div class="toast-body">
        <p class="text-center">L'enregistrement a bien été effectué.<p>
      </div>
    </div>
  </div>
  <?php 
  unset($_SESSION['success']);
}

