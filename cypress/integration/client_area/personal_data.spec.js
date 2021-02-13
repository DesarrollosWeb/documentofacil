describe("Personal data", () => {
    it('should modify personal data', () => {
        cy.fixture("data.json").then(data => {
            cy.visit("datos_personales.php");
            cy.get("#first_name").clear().type(data.first_name);
            cy.get("#last_name").clear().type(data.last_name);
            cy.get("#document_type2").select(data.document_type);
            cy.get("#document_number").clear().type(data.document_number);
            cy.get("#billing_address_1").clear().type(data.address);
            cy.get("#billing_city").clear().type(data.city);
            cy.get("#billing_state").type(data.state);
            cy.get("#billing_postcode").clear().type(data.postcode);
            cy.get("#billing_phone").clear().type(data.phone);
            cy.get("#billing_email").clear().type(data.email);
            cy.get("#nationality").clear().type(data.nationality);
            cy.get('.btn-success').click();
            cy.contains("Operación realizada con éxito.");

            cy.get("#first_name").should("have.value", data.first_name);
            cy.get("#last_name").should("have.value", data.last_name);
            cy.get("#document_type2").should("have.value", data.document_type);
            cy.get("#document_number").should("have.value", data.document_number);
            cy.get("#billing_address_1").should("have.value", data.address);
            cy.get("#billing_city").should("have.value", data.city);
            cy.get("#billing_state").should("have.value", data.state);
            cy.get("#billing_postcode").should("have.value", data.postcode);
            cy.get("#billing_phone").should("have.value", data.phone);
            cy.get("#billing_email").should("have.value", data.email);
            cy.get("#nationality").should("have.value", data.nationality);
        });
    });
});