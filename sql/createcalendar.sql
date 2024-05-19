/* ORIGINAL */
/* Replace Calendar with calendar id */
CREATE TABLE Calendar (
  id binary(16) PRIMARY KEY,
  start DATETIME NOT NULL,
  end DATETIME NOT NULL,
  subject VARCHAR(255),
  description VARCHAR(1023),
  added TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  modified TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE Users (
  user_id binary(16) PRIMARY KEY,
  name VARCHAR(255),
  pass VARCHAR(255),
  created TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  modified TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE Calendars (
  calendar_id binary(16),
  owner binary(16), 
  created TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  modified TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY(calendar_id),
  FOREIGN KEY (owner) REFERENCES Users(user_id)
);

/* Replace 'Events' with calendar_id for per user calendar. */
CREATE TABLE Events (
  event_id binary(16) NOT NULL,
  calendar binary(16) NOT NULL,
  start DATETIME NOT NULL,
  end DATETIME NOT NULL,
  subject VARCHAR(255),
  description VARCHAR(1023),
  created TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  modified TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY(event_id),
  FOREIGN KEY (calendar) REFERENCES Calendar(calendar_id) 
);