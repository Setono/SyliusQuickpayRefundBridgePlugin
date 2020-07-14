@quickpay-refunds
Feature: Refunds can be made through QuickPay
  As an Administrator
  In order to make refunds though QuickPay
  I want to select the QuickPay payment to refund

  Background:
    Given the store operates on a single channel in "United States"
    And the store has a product "Sylius T-Shirt" priced at "$10.00"
    And the store ships everywhere for free
    And the store allows paying with credit card via QuickPay
    And a customer "john.doe@example.com" placed an order "#00000042"
    And the customer bought 3 "Sylius T-Shirt" products
    And the customer chose "Free" shipping method to "United States" with "Credit card via QuickPay" payment
    And this order is already paid
    And a QuickPay payment has been created
    And I am logged in as an administrator

  @ui
  Scenario: When refunding, I should be able to select a payment as source of the refund
    When I want to refund some units of order "#00000042"
    Then I should be able to choose the QuickPay payment to refund
