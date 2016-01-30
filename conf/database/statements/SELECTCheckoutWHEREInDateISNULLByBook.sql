SELECT
	`Checkout`.`CheckoutID`
FROM
	`LarssonLibrary`.`Checkout`
WHERE
	`Checkout`.`BookID` = :BookID
	AND `Checkout`.`InDate` IS NULL
;
