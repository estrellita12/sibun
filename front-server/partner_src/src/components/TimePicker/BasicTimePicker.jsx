// import * as React from "react";
// import { DemoContainer } from "@mui/x-date-pickers/internals/demo";
// import { AdapterDayjs } from "@mui/x-date-pickers/AdapterDayjs";
// import { LocalizationProvider } from "@mui/x-date-pickers/LocalizationProvider";
// import { TimePicker } from "@mui/x-date-pickers/TimePicker";
// import dayjs from "dayjs";

// export default function BasicTimePicker({ setTime, time }) {
//   const onChangeSelectedTime = (value) => {
//     const dayjsformat = dayjs(value).format("HH:MM");
//     setTime(dayjsformat);
//   };

//   return (
//     <LocalizationProvider dateAdapter={AdapterDayjs}>
//       <DemoContainer components={["TimePicker"]}>
//         <TimePicker
//           label={time}
//           minutesStep={30}
//           onChange={(value) => onChangeSelectedTime(value)}
//         />
//       </DemoContainer>
//     </LocalizationProvider>
//   );
// }

import * as React from "react";
import { DemoContainer } from "@mui/x-date-pickers/internals/demo";
import { AdapterDayjs } from "@mui/x-date-pickers/AdapterDayjs";
import { LocalizationProvider } from "@mui/x-date-pickers/LocalizationProvider";
import { TimePicker } from "@mui/x-date-pickers/TimePicker";
import dayjs from "dayjs";

export default function BasicTimePicker({ setTime, time }) {
  const onChangeSelectedTime = (value) => {
    const dayjsformat = dayjs(value).format("HH:mm");
    setTime(dayjsformat);
  };

  return (
    <LocalizationProvider dateAdapter={AdapterDayjs}>
      <DemoContainer components={["TimePicker"]}>
        <TimePicker
          minutesStep={5}
          defaultValue={dayjs(`2022-04-17T${time}`)}
          onChange={(value) => onChangeSelectedTime(value)}
        />
      </DemoContainer>
    </LocalizationProvider>
  );
}
