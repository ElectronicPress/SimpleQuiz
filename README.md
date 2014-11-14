### Introduction ###

Project on [GitHub](skh@git.drupal.org:sandbox/skh/simplequiz.git) and [Drupal](https://www.drupal.org/sandbox/skh/simplequiz).

### Optional, Included Addons ###

#### SimpleQuiz UX

#### SimpleQuiz Certificate
- Requires [private file access][pfa] to be available.

### Installation ###
`drush en simplequiz`.  Optionally enable addon(s) mentioned above.

### Configuration ###
View the `configure` link on the modules page (admin/config/simplequiz).

If you are looking for a *Course* style configuration with a SimpleQuiz 
included, try pairing this module with the core [book][book] module and adding 
it as a page in the book.

### Definitions & Vocabulary ###

#### The `mod` Directory ###
- core:
- addon: 

#### Content Types ####
- simplequiz: The main node type for a quiz.
- simplequiz\_question: A question attached to a simplequiz.
- simplequiz\_answer: An answer attached to a simplequiz_question.

#### Entity Types
- simplequiz\_submission: Stores information about a user submission to a 
  simplequiz.
- simplequiz\_response: Stores individual responses a user made to a question in 
  a given simplequiz.

### Requirements ###
- The core list, number, and options modules.
- [xautoload][xautoload]: Because PSR-4 is fun.
- [entityreference][er]: To link questions and answers to SimpleQuiz.
- [ief][ief]: For single-form UX on SimpleQuiz content types.

### @todo ###
- Rework theme & preprocess hooks for easier theming.
- Show stats on account page.
- More API hooks / alters.
- SimpleQuiz MultiPage addon.
  - With step navigation.
  - Backwards navigation.
  - Skip.
  - Etc.

[xautoload]: https://www.drupal.org/project/xautoload
[er]: https://www.drupal.org/project/entityreference
[ief]: https://www.drupal.org/project/inline_entity_form
[pfa]: https://www.drupal.org/documentation/modules/file#access
[book]: https://www.drupal.org/documentation/modules/book
