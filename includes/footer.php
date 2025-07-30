</main>
    
    <footer class="main-footer">
        <div class="footer-container">
            <div class="footer-section">
                <h4>Iron4Software SARL</h4>
                <p>📍 DataScientest, France</p>
                <p>📧 contact@iron4software.fr</p>
                <p>👨‍💻 Développé  la team: Ludo, Damien, Emilio</p>
            </div>
            
            <div class="footer-section">
                <h4>Informations Système</h4>
                <p><strong>Version:</strong> <?php echo APP_VERSION; ?></p>
                <p><strong>PHP:</strong> <?php echo PHP_VERSION; ?></p>
                <p><strong>Serveur:</strong> <?php echo $_SERVER['SERVER_SOFTWARE'] ?? 'Apache'; ?></p>
            </div>
        </div>
        
        <div class="footer-bottom">
            <p>&copy; 2025 Iron4Software SARL</p>
        </div>
    </footer>
</body>
</html>
