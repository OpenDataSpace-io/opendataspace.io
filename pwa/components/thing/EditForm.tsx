import { type NextPage } from "next";
import Head from "next/head";
import Image from "next/image";
import Link from "next/link";
import { useEffect, useState } from "react";
import { useMutation } from "react-query";
import Typography from "@mui/material/Typography";
import Breadcrumbs from "@mui/material/Breadcrumbs";
import FavoriteBorderIcon from "@mui/icons-material/FavoriteBorder";
import FavoriteIcon from "@mui/icons-material/Favorite";
import Button from "@mui/material/Button";
import ButtonGroup from "@mui/material/ButtonGroup";
import { signIn, useSession } from "next-auth/react";

import { type Thing } from "@/types/Thing";
import { useMercure } from "@/utils/mercure";
import { useThing } from "@/utils/thing";
import { fetch, type FetchError, type FetchResponse } from "@/utils/dataAccess";
import { type PagedCollection } from "@/types/collection";
import { Loading } from "@/components/common/Loading";
import ShowProperties from "./ShowProperties";

import { RJSFSchema } from '@rjsf/utils';
import validator from '@rjsf/validator-ajv8';
import Form from '@rjsf/mui';

interface Props {
  data: Thing;
  hubURL: string | null;
  page: number;
}

// https://rjsf-team.github.io/react-jsonschema-form/docs/

const schema2: RJSFSchema = {
  "title": "Test Place Form",
  //"description": "A simple form example.",
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
          "date": {
            "type": "string",
            "title": "Datum",
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

const uiSchema = {
  "description": {
    "ui:widget": "textarea"
  },
}

const generateSchema = (data: any, excludeFields = []) => {
  const schema = {
    //title: "Dynamisches Formular",
    type: "object",
    properties: {} as { [key: string]: { type: string, title: string } },
  };

  for (const key in data) {
    if (!excludeFields.includes(key)) {
      if (typeof data[key] === 'object' && data[key] !== null && !Array.isArray(data[key])) {
        schema.properties[key] = generateSchema(data[key]);
      } else {
        schema.properties[key] = {
          type: typeof data[key],
          title: key,
        };
      }
    }
  }


  return schema;
};

const generateFromData = (data: any) => {
  const formData: { [key: string]: any } = {};

  for (const key in data) {
    if (typeof data[key] === 'object' && data[key] !== null && !Array.isArray(data[key])) {
      formData[key] = generateFromData(data[key]);
    } else {
      formData[key] = data[key];
    }
  }

  return formData;
};

const log = (type: any) => console.log.bind(console, type);

export const EditForm: NextPage<Props> = ({ data, hubURL, page }) => {
  const { data: session, status } = useSession();
  const item = useMercure(data, hubURL);

  for (let index = 0; index < item.length; index++) {
    const element = item[index];
    
  }

  /*const formData = {
    name: item["name"],
    description: item["description"],
  };*/

  const excludeFields: Array<string | never[]> = [
    "@context",
    "@id",
    //"@type",
    "id",
  ];

  const schema = generateSchema(data, excludeFields as never[]);
  const formData = generateFromData(data);

  if (status === "loading") {
    return <Loading />;
  }

  if (session?.error) {
    return <div>{session.error}</div>;
  }

  if (item) {
    return (
      <>
        <Head>
          <title>{`${item["name"]}`}</title>
        </Head>
        <div className="container mx-auto max-w-7xl items-center justify-between p-6 lg:px-8">
          <Form
              schema={schema}
              uiSchema={uiSchema}
              formData={formData}
              validator={validator}
              onChange={log('changed')}
              onSubmit={log('submitted')}
              onError={log('errors')}
          />
        </div>
      </>
    );

  }
};