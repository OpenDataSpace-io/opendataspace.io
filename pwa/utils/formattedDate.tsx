import React from 'react';
import { useTranslation } from 'next-i18next';

interface DateProps {
  dateString: string;
}

const FormattedDate: React.FC<DateProps> = ({ dateString }) => {
  const { i18n } = useTranslation();
  const date = new Date(dateString);
  const formattedDate = new Intl.DateTimeFormat(i18n.language).format(date);

  return <span>{formattedDate}</span>;
};

export default FormattedDate;