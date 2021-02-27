describe("Site Language", () => {
    it('should test', () => {
        cy.visit('test.php', {
            onBeforeLoad(win) {
                Object.defineProperty(win.navigator, 'language', {value: 'pt'});
                Object.defineProperty(win.navigator, 'languages', {value: ['pt']});
                Object.defineProperty(win.navigator, 'accept_languages', {value: ['pt']});
            },
            headers: {
                'Accept-Language': 'pt',
            },
        });
        cy.contains("/Users/anyulled/Sites/wordpress/test.php:4:string 'pt' (length=2)");
    });
    it('should be able to navigate in spanish', () => {
        cy.visit("tramites.php", {
            onBeforeLoad(win) {
                Object.defineProperty(win.navigator, 'language', {value: 'es'});
                Object.defineProperty(win.navigator, 'languages', {value: ['es']});
                Object.defineProperty(win.navigator, 'accept_languages', {value: ['es']});
            },
            headers: {
                'Accept-Language': 'es',
            },
        });
        cy.contains("Área de Clientes");
    });

    it('should be able to navigate in portuguese', () => {
        cy.visit("tramites.php", {
            onBeforeLoad(win) {
                Object.defineProperty(win.navigator, 'language', {value: 'pt'});
                Object.defineProperty(win.navigator, 'languages', {value: ['pt']});
                Object.defineProperty(win.navigator, 'accept_languages', {value: ['pt']});
            },
            headers: {
                'Accept-Language': 'pt',
            },
        });
        cy.contains("Área do cliente");
    });
});