import { RJSFSchema } from '@rjsf/utils';
import validator from '@rjsf/validator-ajv8';
import Form from '@rjsf/mui';
import Head from "next/head";

// https://rjsf-team.github.io/react-jsonschema-form/docs/

const schema: RJSFSchema = {
  title: 'Todo',
  type: 'object',
  required: ['title'],
  properties: {
    title: { type: 'string', title: 'Title', default: 'A new task' },
    done: { type: 'boolean', title: 'Done?', default: false },
  },
};

const schema2: RJSFSchema = {
  "title": "Test Place Form",
  "description": "A simple form example.",
  "type": "object",
  "required": [
    "name",
  ],
  "properties": {
    "name": {
      "type": "string",
      "title": "Name",
      "default": "Test Thing"
    },
    "description": {
      "type": "string",
      "title": "Description",
    },
    "address": {
      "type": "object",
      "properties": {
        "streetAddress": {
          "type": "string",
        },
        "addressLocality": {
          "type": "string"
        },
        "postalCode": {
          "type": "string"
        },
        "addressCountry": {
          "type": "string"
        }
      },
      "required": [
        "street_address",
        "city",
      ]
    },
    "geo": {
      "type": "object",
      "title": "Geo Location",
      "properties": {
        "latitude": {
          "type": "number",
          "title": "Latitude",
        },
        "longitude": {
          "type": "number",
          "title": "Longitude",
        }
      }
    },
    "telephone": {
      "type": "string",
      "title": "Telephone",
      "minLength": 10
    },
    "email": {
      "type": "string",
      "title": "Email",
      "format": "email"
    },
    "website": {
      "type": "string",
      "title": "Website",
      "format": "uri"
    },
    "openingHours": {
      "type": "array",
      "title": "Öffnungszeiten",
      "items": {
        "type": "object",
        "properties": {
          "dayOfWeek": {
            "type": "string",
            "title": "Wochentag",
            "enum": [
              "Monday",
              "Tuesday",
              "Wednesday",
              "Thursday",
              "Friday",
              "Saturday",
              "Sunday"
            ],
            "enumNames": [
              "Montag",
              "Dienstag",
              "Mittwoch",
              "Donnerstag",
              "Freitag",
              "Samstag",
              "Sonntag"
            ]
          },
          "opens": {
            "type": "string",
            "title": "Öffnet",
            "format": "time"
          },
          "closes": {
            "type": "string",
            "title": "Schließt",
            "format": "time"
          }
        }
      }
    },
    "openingHoursSpecification":{
      "type": "array",
      "title": "spezifische Öffnungszeiten",
      "items": {
        "type": "object",
        "properties": {
          "validFrom": {
            "type": "string",
            "title": "Von",
            "format": "date"
          },
          "validThrough": {
            "type": "string",
            "title": "Bis",
            "format": "date"
          },
          "opens": {
            "type": "string",
            "title": "Öffnet",
            "format": "time"
          },
          "closes": {
            "type": "string",
            "title": "Schließt",
            "format": "time"
          },
          "closed": {
            "type": "boolean",
            "title": "Geschlossen",
            "default": false
          }
        }
      }
    },
  }
}

const log = (type:any) => console.log.bind(console, type);

const Playground = () => {
    return (
      <>
        <Head>
          <title>Form Playground!</title>
        </Head>
        <div className="container mx-auto max-w-7xl items-center justify-between p-6 lg:px-8">
          <Form
              schema={schema2}
              validator={validator}
              onChange={log('changed')}
              onSubmit={log('submitted')}
              onError={log('errors')}
          />
        </div>
      </>
    );
};

export default Playground;