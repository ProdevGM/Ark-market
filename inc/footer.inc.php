    </main>

    <footer class=" bg-dark mt-5">
        <div class="container">
          <div class="row text-center pt-5 pb-5">
            <div class="col-6">
              <a href="">Mentions légales</a>
            </div>
            <div class="col-6">
              <a href="">Conditions générales de ventes</a>
            </div>
          </div>
          <p class="m-0 text-center text-white">Copyright &copy; ARK-MARKET 2021</p>
        </div>
    </footer>

    <!-- Tranformation des tab de init.inc.php en string js -->
    <script>
      var plateformeSeule = "<?= $plateforme_seule ?>";
      var plateforme = "<?= $plateforme ?>";
      var creature = "<?= $creature_js ?>";
      var selle = "<?= $selle_js ?>";
      var arme = "<?= $arme_js ?>";
      var armure = "<?= $armure_js ?>";
    </script>
    <script src="<?php echo URL; ?>js/jquery.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="<?php echo URL; ?>js/bootstrap.bundle.min.js"></script>
    <script src="<?php echo URL; ?>js/ark_market.js"></script>

	</body>
</html>
