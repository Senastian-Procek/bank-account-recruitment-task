**Code Sample:**

- **PHP 8.x** (framework-agnostic)
- **DDD** (domain, code structure, relationships).
- **Unit tests** (full scenario coverage).

**Requirements:**

**Bank Account:**
1. Has an assigned currency.
2. Supports receiving (credit) and sending (debit) money in the same currency.
3. Balance is calculated based on operations.
4. Debit transactions include a 0.5% transaction fee.
5. Sending money is allowed only if there are sufficient funds.
6. Maximum of 3 debits per day.

**Payment:**
- Includes an amount and currency.  