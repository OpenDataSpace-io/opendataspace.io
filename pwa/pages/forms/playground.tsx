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
  "title": "A registration form",
  "description": "A simple form example.",
  "type": "object",
  "required": [
    "firstName",
    "lastName"
  ],
  "properties": {
    "firstName": {
      "type": "string",
      "title": "First name",
      "default": "Chuck"
    },
    "lastName": {
      "type": "string",
      "title": "Last name"
    },
    "age": {
      "type": "integer",
      "title": "Age"
    },
    "bio": {
      "type": "string",
      "title": "Bio"
    },
    "password": {
      "type": "string",
      "title": "Password",
      "minLength": 3
    },
    "telephone": {
      "type": "string",
      "title": "Telephone",
      "minLength": 10
    }
  }
}

const log = (type:any) => console.log.bind(console, type);

const Playground = () => {
    return (
      <>
        <Head>
          <title>Form Playground!</title>
        </Head>
  
        <Form
            schema={schema2}
            validator={validator}
            onChange={log('changed')}
            onSubmit={log('submitted')}
            onError={log('errors')}
        />,
      </>
    );
};

export default Playground;