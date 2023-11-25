import { Button, ShowButtonProps, useRecordContext } from "react-admin";
import { getItemPath } from "@/utils/dataAccess";
import slugify from "slugify";
import VisibilityIcon from "@mui/icons-material/Visibility";

export const ShowButton = (props: ShowButtonProps) => {
  const record = useRecordContext(props);

  return record ? (
    // @ts-ignore
    <Button label={props.label} target="_blank" href={getItemPath({
      id: record["@id"].replace(/^\/admin\/things\//, ""),
      slug: slugify(`${record.name}`, { lower: true, trim: true, remove: /[*+~.(),;'"!:@]/g }),
    }, "/things/[id]")}>
      <VisibilityIcon/>
    </Button>
  ) : null;
};
ShowButton.defaultProps = { label: "ra.action.show" };
