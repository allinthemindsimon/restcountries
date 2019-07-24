A test looking at the "Countries RESTful API.

Assumptions:

Although a secure laravel framework is used, there is no authorisation required,
/home will take you to the start page.

It is assumed that the user will mostly use the 'name' field and reasonably type theri best guess at a partial or full name

It will only bring back 1 result and update the DB with 1 result for 'slimness and certainty'.

Searches like 'uk', 'usa' will pick up from the alt spellings field while 'venez' will pick up from a lik name field.
typing in less than 3 or 4 letters will return multiple options and the system will return a warning message.
Any reasonable search apart from 'austr' will return a result, within reason.

searching on non-name fields is less useful as there are sometimes multiple results.

In Currencies, 'HKD' will return Hong Kong, while 'USD' would return multiple values and therefore a warning.

This is how I understood the test and I hope that the coding matches this approach.

If I were to do it again, I woul ask for clarification and return multiple results as a choice, then once picked use the current country result display page.
I would only update the database with the chosen country unless the goal is to swiftly fill the database in which case we could either add all returned records or do a parallel call in the background to pull in all of the data (there is not that much and it would load very quickly.
