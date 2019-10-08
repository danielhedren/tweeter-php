# tweeter-php

### Database
The database needs to handle tweets, retweets and comment threads.

#### Logical model
`User(id, email, displayname, password, date)`

`Comment(id, user_id, parent_id, content, date)`

`Vote(id, user_id, comment_id)`